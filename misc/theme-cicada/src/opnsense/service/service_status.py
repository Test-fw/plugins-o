#!/usr/local/bin/python
import sys
from decrypt_data import decrypt_license
from datetime import datetime
import json


universal_list= ['IPS/IDS','Email','Avirus','Aspam','Email','WAF','DNS','AppFil']
license_set= set(universal_list)

def show_lic():

    Data={
        "lic_type":lic_type,
        "lic_service": lic_service,
        "start_date":start_date,
        "end_date":end_date
    }
    data= json.dumps(Data)
    
    return data



from datetime import datetime

def check_valid_lic():
    if not data:
        return False
    startdate_obj = datetime.strptime(date_str.strip(), "%d-%m-%Y").date()
    enddate_obj = datetime.strptime(end_date.strip(), "%d-%m-%Y").date()
    current_date = datetime.now().date()
    if startdate_obj <= current_date <= enddate_obj:
        return True
    else:
        return False

def lic_status():
    valid_lic = check_valid_lic()
    # print("Valid License:", valid_lic)
    if not valid_lic:
        return ["", "", ""]
    elif lic_type == 'Demo':
        return [lic_type, lic_service, end_date]
    elif lic_type == 'App_lic':
        return [lic_type, lic_service, end_date]
    
def lic_only():
    valid_lic= check_valid_lic()
    if not valid_lic:
        return "upload license"
    else:
        return {"services":lic_service}
    
def no_lic():
    no_lics = [item for item in universal_list if item not in lic_service]
    return no_lics

def json_data():
    Data={
        "license":lic_service,
        "lic_type":lic_type,
        "end_date":end_date
    }
    json_datas=json.dumps(Data)
    return json_datas


if __name__ == "__main__":
    detail = decrypt_license()
    data = detail[1]
    start_date = data.get('Start_date')
    # date_str= start_date
    end_date = data.get("End_date")
    lic_type = data.get('License_Type')
    lic_service = data.get('Lic_service') 

    if sys.argv[1] == 'lic_status':
        str1 = lic_status()
        print(str1)

    if sys.argv[1] == 'show_lic':
        str1 = show_lic()
        print(str1)

    if sys.argv[1] == 'lic_only':
        str1 = lic_only()
        print(str1)

    if sys.argv[1] == 'check_valid':
        str1 = check_valid_lic()
        print(str1)

    if sys.argv[1] == 'no_lic':
        str1 = no_lic()
        print(str1)
    
    if sys.argv[1] == 'json':
        str1 = json_data()
        print(str1)