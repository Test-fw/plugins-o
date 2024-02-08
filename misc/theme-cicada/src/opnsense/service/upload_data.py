import os
import re
import json
import subprocess
import shutil
from decrypt_data import decrypt_license

# Function to keep only one .lic file in the /tmp directory

def move(tmp_dir='/conf', exp_dir='/conf/exp', file_extension='.lic'):
    """
    Moves older files with the specified extension from the source directory to the destination directory.
    
    Args:
        tmp_dir (str): The source directory where files are checked for the specified extension.
        exp_dir (str): The destination directory where older files will be moved.
        file_extension (str): The file extension to filter files (e.g., '.lic').
    """
    # Ensure the destination directory exists
    if not os.path.exists(exp_dir):
        os.makedirs(exp_dir)

    # List all files in the source directory
    files = os.listdir(tmp_dir)

    # Filter the list to only include files with the specified extension
    filtered_files = [file for file in files if file.endswith(file_extension)]

    # Sort the filtered files by modification time (oldest first)
    filtered_files.sort(key=lambda x: os.path.getmtime(os.path.join(tmp_dir, x)))

    # If there are older files, move them to the destination directory
    if len(filtered_files) > 1:
        for old_file in filtered_files[:-1]:
            old_file_path = os.path.join(tmp_dir, old_file)
            new_file_path = os.path.join(exp_dir, old_file)
            shutil.move(old_file_path, new_file_path)

    # Return the name of the latest file (if any)
    latest_file = filtered_files[-1] if filtered_files else None
    return latest_file

# Example usage:
latest_lic_file = move('/conf', '/conf/exp', '.lic')
if latest_lic_file:
    print(f"Latest .lic file: {latest_lic_file}")
else:
    print("No .lic files in /conf")


def upload_license():
    
    dict_detail = decrypt_license()
    dict_data = dict_detail[1]

    if not dict_data:
        return False
#$Lic_names=['IDS/IPS', 'App_Fil', 'URL_Filter', 'Sndbx', 'Avirus', 'ASpam', 'Email', 'WAF', 'DNS', 'SLB'];

    Sr_No = dict_data['Sr_no']
    model_num = dict_data['model_no']
    # output = subprocess.check_output(['ifconfig', 'xn0'])
    # output = output.decode('utf-8')
    '''
    with open('/etc/serial', 'r') as f:
        Serial_number = f.read()
    with open('/etc/model', 'r') as fi:
        Model_Num = fi.read()
    # with open('/etc/mac', 'r') as fi:
    #      ether_address = fi.read()'''

    #Testing with custom serial and model number

    Serial_number="1234567890"
    Model_Num="1234567890"
    
    if ((Serial_number == Sr_No) and (model_num == Model_Num)):
        print("sucess")
        return True
    else:
        print("Uploaded data false")
        return False

if __name__ == "__main__":
    upload_license()
    move()