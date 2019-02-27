from flask import Flask, render_template, request, json, Response
from flask_login import LoginManager, UserMixin,login_required, login_user, logout_user

from flask_cors import CORS
from backend.mappers.TimeslotMapper import TimeslotMapper
from backend.mappers.CartMapper import CartMapper
from backend.mappers.UserMapper import UserMapper

from backend.mappers.LoginMapper import LoginMapper
app = Flask(__name__,
            static_folder="./dist/static",
            template_folder="./dist")

CORS(app)
timeslot_mapper = TimeslotMapper(app)
cart_mapper = CartMapper(app)
user_mapper = UserMapper(app)
login_mapper = LoginMapper(app)

login_manager = LoginManager()
login_manager.init_app(app)

app.secret_key='Nora_is_a_________just_give_us_100_percent'

@app.route('/', defaults={'path': ''})
@app.route('/<path:path>')
def catch_all(path):
    return render_template("index.html")

@app.route('/getDoctors')
def get_doctors():
    return json.dumps(user_mapper.get_doctors())

# some protected url
@app.route('/homePage')
@login_required
def home():
    return ("you logged in")


@app.route('/getAppointments')
def get_all_appointments():
    return json.dumps([obj.__dict__ for obj in timeslot_mapper.get_all_appointments()])

@app.route('/addAppointment', methods=['POST'])
def add_appointment():
    return timeslot_mapper.book_appointment(request.get_json())

@app.route('/cancelAppointment', methods=['POST'])
def cancel_appointment():
    return timeslot_mapper.cancel_appointment(request.get_json())

@app.route('/getAvailabilities', methods=['POST'])
def get_availabilities():
    print([obj.__dict__ for obj in timeslot_mapper.get_availabilities(request.get_json())])
    return json.dumps([obj.__dict__ for obj in timeslot_mapper.get_availabilities(request.get_json())])

@app.route('/getCart')
def get_cart():
    return cart_mapper.get_cart()


@login_manager.user_loader
def user_loader(code):
    return login_mapper.user_loader(code)
