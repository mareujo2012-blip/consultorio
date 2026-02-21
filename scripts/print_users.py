import urllib.request
import json

try:
    response = urllib.request.urlopen("https://consultorio.marcodaros.com.br/api/debug-users")
    data = response.read().decode('utf-8')
    users = json.loads(data)
    for u in users:
        print(f"ID: {u['id']} | Name: {u['name']} | Email: {u['email']}")
except Exception as e:
    print(e)
