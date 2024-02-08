import os
import re
import json
from Crypto.PublicKey import RSA
import xml.etree.ElementTree as ET
from Crypto.Cipher import PKCS1_OAEP


def decrypt_license():
    """
    This function will decrypt encrypted data of license portel and 
    return data in dictionary.
    
    :rtype: List Element 
    :Author: Veritawall Technologies Pvt. Ltd.
    """
    extension = ".lic"
    folder_path = "/conf/"
    if not os.listdir(folder_path):
        return False, ""
    for filename in os.listdir(folder_path):
        if not filename.endswith(extension):
            continue
        if not os.path.join(folder_path, filename):
            return False, ""
        with open(os.path.join(folder_path, filename), 'rb')as f:
            ciphertext = f.read()
        
        # tree = ET.parse('/conf/config.xml')
        # root_data = tree.getroot()
        
        # private_keys = root_data.find("./private_key").text
        # print("1",root_data,"2",private_keys,"Encode")
        
        # if not private_keys:
        #     return False, "No keys Found"

        folder_path='/conf'
        private_key_path = os.path.join(folder_path, 'private_key.pem')
        with open(private_key_path, 'r') as f :
            private_key = f.read()
        key = RSA.importKey(private_key)
        cipher = PKCS1_OAEP.new(key)
        plaintext = cipher.decrypt(ciphertext)
        dict_data = json.loads(plaintext)
        # print(dict_data)
        return True, dict_data
    
    return False,"No match found"

# decrypt_license()