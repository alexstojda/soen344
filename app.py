from flask import Flask, render_template, request, json

from flask_cors import CORS
from backend.mappers.MapperFactory import MapperFactory
from backend.mappers.TimeslotMapper import TimeslotMapper
from backend.mappers.CartMapper import CartMapper
from backend.mappers.UserMapper import UserMapper

app = Flask(__name__,
            static_folder="./dist/static",
            template_folder="./dist")

CORS(app)
timeslot_mapper = MapperFactory.getMapper(app, "Timeslot")
cart_mapper = MapperFactory.getMapper(app, "Cart")
user_mapper = MapperFactory.getMapper(app, "User")

@app.route('/', defaults={'path': ''})
@app.route('/<path:path>')
def catch_all(path):
    return render_template("index.html")

@app.route('/getDoctors')
def get_doctors():
    return json.dumps(user_mapper.get_doctors())

@app.route('/getAppointments')
def get_all_appointments():
    return json.dumps([obj.__dict__ for obj in timeslot_mapper.get_all_appointments()])

@app.route('/addAppointment', methods=['POST'])
def add_appointment():
    return timeslot_mapper.book_appointment(request.get_json())

@app.route('/cancelAppointment', methods=['POST'])
def cancel_appointment():
    return timeslot_mapper.cancel_appointment(request.get_json())

@app.route('/addAvailability', methods=['POST'])
def add_availability():
    return timeslot_mapper.add_availability(request.get_json())

@app.route('/getAvailabilities', methods=['POST'])
def get_availabilities():
    return json.dumps([obj.__dict__ for obj in timeslot_mapper.get_availabilities(request.get_json())])

@app.route('/getCart')
def get_cart():
    return cart_mapper.get_cart()
