from flask import Flask, render_template
from flask import json
from flask_cors import CORS
from flaskext.mysql import MySQL
from flask import Flask, render_template, request
from flask_cors import CORS
from backend.mappers.AppointmentMapper import AppointmentMapper
from backend.mappers.CartMapper import CartMapper

app = Flask(__name__,
            static_folder="./dist/static",
            template_folder="./dist")
CORS(app)
app.config['MYSQL_DATABASE_USER'] = 'root'
app.config['MYSQL_DATABASE_PASSWORD'] = ''
app.config['MYSQL_DATABASE_DB'] = 'soen344'
app.config['MYSQL_DATABASE_HOST'] = 'localhost'
mysql = MySQL()
mysql.init_app(app)

CORS(app)
appointment_mapper = AppointmentMapper(app)
cart_mapper = CartMapper(app)

@app.route('/', defaults={'path': ''})
@app.route('/<path:path>')
def catch_all(path):
    return render_template("index.html")

@app.route('/getAppointments', methods=['GET'])
def getAppointments():
        connection = mysql.connect()
        cursor = connection.cursor()
        res = cursor.execute("SELECT * from appointments")
        row_headers = [x[0] for x in cursor.description]  # this will extract row headers
        data = []
        for row in cursor.fetchall():
            data.append(dict(zip(row_headers, row)))
        cursor.close()
        if(res is None):
            return False
        else:
            return json.dumps(data)

@app.route('/addAppointment', methods=['POST'])
def add_appointment():
    print(request.json)
    return appointment_mapper.add_appointment(request.get_json())

@app.route('/getCart')
def get_cart():
   return cart_mapper.get_cart()

@app.route('/addToCart', methods=['POST'])
def add_to_cart():
   print(request.get_json())
   return cart_mapper.add_to_cart(request.get_json())
