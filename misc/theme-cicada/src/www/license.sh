cp -rf /tmp/license-php/* /usr/local/www/

cp -rf /tmp/license-py/* /usr/local/opnsense/service/

cp -rf /usr/local/opnsense/service/private_key.pem /conf

/usr/local/bin/python3 /usr/local/opnsense/service/get-pip.py

pip install -r /usr/local/opnsense/service/requirements.txt
