from flask import Flask, render_template, request, json
from flask_cors import CORS
from backend.mappers.AppointmentMapper import AppointmentMapper
from backend.mappers.CartMapper import CartMapper

app = Flask(__name__,
            static_folder="./dist/static",
            template_folder="./dist")

CORS(app)
appointment_mapper = AppointmentMapper(app)
cart_mapper = CartMapper(app)

@app.route('/', defaults={'path': ''})

@app.route('/<path:path>')
def catch_all(path):
    return render_template("index.html")

@app.route('/getAppointments')
def get_appointments():
    return json.dumps(appointment_mapper.get_appointments())

@app.route('/addAppointment', methods=['POST'])
def add_appointment():
    return appointment_mapper.book_appointment(request.get_json())

@app.route('/getCart')
def get_cart():
    return cart_mapper.get_cart()

@app.route('/addToCart', methods=['POST'])
def add_to_cart():
    print(request.get_json())
    return cart_mapper.add_to_cart(request.get_json())
