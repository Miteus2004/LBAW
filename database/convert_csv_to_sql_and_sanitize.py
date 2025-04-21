import os
import csv
import re
import random

# DON'T USE HERE!!! It needs a directory with csv files to work properly.

# We used fabricate (a tool that belongs to mockaroo https://fabricate.mockaroo.com/) but since we didn't want to pay, and the free tier only allowed for 100 rows at a time.
# So we added 100 rows for each table multiple times and then combined them into their respective csv file.
# But then we had problems with the foreign key constraints, so we converted all the data of the csv files into sql files and also generate some random data to fill the votes, notifications and bookmarks tables.
# We also had to parse the data provided has putting it directly into the database would cause problems with SQL injection and syntax errors.

MIN_NUMBER_OF_RANDOM_VOTES_PER_ITEM = 0 # minimum number of random votes per item
MAX_NUMBER_OF_RANDOM_VOTES_PER_ITEM = 10 # maximum number of random votes per item
CHANCE_OF_NOTIFICATION = 0.2 # 20% chance of a notification being generated

# the directory where the CSV files are located, the name of each file should be the same as the table name and the first row should contain the column names
INPUT_DIRECTORY = 'csv_files_input'
# the directory where the generated SQL files will be saved (including the combined file with all the SQL statements)
OUTPUT_DIRECTORY = 'sql_files_output'
# the file that will contain all the generated SQL statements
COMBINED_OUTPUT_FILE = 'all_converted_sql.sql'
# the order in which the tables should be processed (they need to be processed in this order because of foreign key constraints)
ORDER = [
    'users',
    'questions',
    'bookmarks',
    'answers',
    'question_comments',
    'answer_comments',
    'tags',
    'question_tags',
    'badges',
    'user_badges',
    'faq'
]

# remove special characters that could cause SQL injection or syntax errors
def remove_special_characters_that_could_cause_problems(value):
    value = re.sub(r"[\'\";\\-]", "", value)  # remove ' , " , ; , -
    value = re.sub(r"--", "", value)  # remove --
    return value

# reads a CSV file and generates the SQL statements to insert the data into the database
def csv_to_sql(csv_file, table_name):
    with open(csv_file, newline='') as csvfile:
        reader = csv.reader(csvfile)
        headers = next(reader)  # get the sql column from the first row
        columns = ', '.join(headers)
        
        sql_statements = []
        data = []
        values_list = []
        for row in reader:
            sanitized_row = [remove_special_characters_that_could_cause_problems(value) for value in row]
            values = ', '.join(f"'{value}'" for value in sanitized_row)
            values_list.append(f"({values})")
            data.append(dict(zip(headers, row)))
        
        if len(values_list) > 0:
            sql_statements.append(f"INSERT INTO {table_name} ({columns}) VALUES {',\n'.join(values_list)};")
        
        return '\n'.join(sql_statements), data

# works for questions votes and answers votes
def generate_votes(users, items, table_name, item_id_field):
    sql_statements = []
    values_list = []
    for item in items:
        item_id = item['id'] # because in the schema we don't have an id for the vote, we use the id of the item the vote refers to (=question or answer) with the id of the user
        user_id = item['user_id']
        num_votes = random.randint(MIN_NUMBER_OF_RANDOM_VOTES_PER_ITEM, MAX_NUMBER_OF_RANDOM_VOTES_PER_ITEM)
        voters = random.sample([user['id'] for user in users if user['id'] != user_id], num_votes) # get a random sample of users to vote on the item, but the user who created the item can't vote on it
        for voter in voters: # for each voter, we generate a random vote (either positive or negative)
            vote_type = random.choice(['positive', 'negative'])
            values_list.append(f"('{vote_type}', {item_id}, {voter})")
    
    if values_list:
        sql_statements.append(f"INSERT INTO {table_name} (vote_type, {item_id_field}, user_id) VALUES {',\n'.join(values_list)};")
    
    return '\n'.join(sql_statements)

# generates notifications and works for both question_comment_notifications and answer_notifications
def generate_notifications(users, items, table_name, item_id_field):
    sql_statements = []
    values_list = []
    for item in items:
        item_id = item['id']  # because in the schema we don't have an id for the notifications, we use the id of the item the notification refers to (=question or answer) with the id of the user
        user_id = item['user_id']
        if random.random() < CHANCE_OF_NOTIFICATION:
            values_list.append(f"({user_id}, {item_id}, CURRENT_TIMESTAMP)")
    
    if values_list:
        sql_statements.append(f"INSERT INTO {table_name} (user_id, {item_id_field}, not_date) VALUES {',\n'.join(values_list)};")
    
    return '\n'.join(sql_statements)

# generates bookmarks for questions
def generate_bookmarks(users, questions, table_name):
    sql_statements = []
    values_list = []
    for question in questions:
        question_id = question['id']
        bookmarkers = random.sample(users, 3) if random.random() < 0.3 else [] # 30% chance of a question being bookmarked by 3 random users
        for user in bookmarkers:
            user_id = user['id']
            values_list.append(f"({user_id}, {question_id})")
    
    if values_list:
        sql_statements.append(f"INSERT INTO {table_name} (user_id, question_id) VALUES {',\n'.join(values_list)};")
    
    return '\n'.join(sql_statements)

# essencially the main function, that will call all the other ones and join the results into a single file
def process_files(input_directory, output_directory, combined_output_file, order):
    if not os.path.exists(output_directory): # create the output directory if it doesn't exist
        os.makedirs(output_directory)
    
    combined_sql_statements = []
    users = []
    questions = []
    answers = []
    question_comments = []
    answer_comments = []

    for table_name in order:
        file_name = table_name + '.csv'
        csv_file = os.path.join(input_directory, file_name)
        if os.path.exists(csv_file):
            # read the CSV file and generate the SQL statements
            sql_output, data = csv_to_sql(csv_file, table_name)
            print(f"Processed {file_name}")

            # store the data to be used later for generating votes, notifications, bookmarks
            if table_name == 'users':
                users = data
            elif table_name == 'questions':
                questions = data
            elif table_name == 'answers':
                answers = data
            elif table_name == 'question_comments':
                question_comments = data
            elif table_name == 'answer_comments':
                answer_comments = data
            
            # write the generated SQL to a file
            output_file_name = table_name + '.sql'
            output_file_name = os.path.join(output_directory, output_file_name)
            open(output_file_name, 'w', newline='\n').write(sql_output)
            
            # add the generated SQL to the list of all SQL statements
            combined_sql_statements.append(sql_output)
            print(f"Generated {output_file_name}")
    
    # generate votes for questions, votes for answers, question_comment notifications, answer notifications, bookmarks
    questions_votes_sql = generate_votes(users, questions, 'questions_votes', 'question_id')
    print(f"Processed questions_votes")
    answers_votes_sql = generate_votes(users, answers, 'answers_votes', 'answer_id')
    print(f"Processed answers_votes")
    question_comment_notifications_sql = generate_notifications(users, question_comments, 'question_comment_notifications', 'question_comments_id')
    print(f"Processed question_comment_notifications")
    answer_notifications_sql = generate_notifications(users, answers, 'answer_notifications', 'answer_id')
    print(f"Processed answer_notifications")
    bookmarks_sql = generate_bookmarks(users, questions, 'bookmarks')
    print(f"Processed bookmarks")

    # write generated SQL to individual files
    generated_files = {
        'questions_votes.sql': questions_votes_sql,
        'answers_votes.sql': answers_votes_sql,
        'question_comment_notifications.sql': question_comment_notifications_sql,
        'answer_notifications.sql': answer_notifications_sql,
        'bookmarks.sql': bookmarks_sql
    }

    # write generated SQL to individual files
    for file_name, sql in generated_files.items():
        output_file_name = os.path.join(output_directory, file_name)
        open(output_file_name, 'w', newline='\n').write(sql)
        combined_sql_statements.append(sql)
        print(f"Generated {file_name}")

    # write all combined SQL statements to the combined output file
    combined_output_path = os.path.join(output_directory, combined_output_file)
    open(combined_output_path, 'w', newline='\n').write('\n'.join(combined_sql_statements))
    
    print("SUCCESS")

process_files(INPUT_DIRECTORY, OUTPUT_DIRECTORY, COMBINED_OUTPUT_FILE, ORDER)
