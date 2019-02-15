from flask import Flask, render_template, request
from flask_cors import CORS
from backend.mappers.AppointmentMapper import AppointmentMapper

app = Flask(__name__,
            static_folder="./dist/static",
            template_folder="./dist")

CORS(app)
appointment_mapper = AppointmentMapper(app)

@app.route('/', defaults={'path': ''})

@app.route('/<path:path>')
def catch_all(path):
    return render_template("index.html")

@app.route('/getAppointments')
def get_appointments():
    return appointment_mapper.get_appointments()

@app.route('/addAppointment', methods=['POST'])
def add_appointment():
    print(request.json)
    return appointment_mapper.add_appointment(request.get_json())