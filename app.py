from flask import Flask, render_template, request, json, Response
from flask_login import LoginManager, UserMixin,login_required, login_user, logout_user

from flask_cors import CORS
from backend.mappers.AppointmentMapper import AppointmentMapper
from backend.mappers.CartMapper import CartMapper
from backend.mappers.UserMapper import UserMapper

from backend.mappers.LoginMapper import LoginMapper
app = Flask(__name__,
            static_folder="./dist/static",
            template_folder="./dist")

app.secret_key='secret_brazzers_code'

CORS(app)
appointment_mapper = AppointmentMapper(app)
cart_mapper = CartMapper(app)
user_mapper = UserMapper(app)
appointment_mapper = AppointmentMapper(app)
login_mapper = LoginMapper(app)
login_mapper = LoginMapper(app)

login_manager = LoginManager()
login_manager.init_app(app)

app.secret_key='secret_brazzers_code'

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
def get_appointments():
  return json.dumps(appointment_mapper.get_appointments())

@app.route('/addAppointment', methods=['POST'])
def add_appointment():
  return appointment_mapper.book_appointment(request.get_json())

@app.route('/cancelAppointment', methods=['POST'])
def cancel_appointment():
    return appointment_mapper.cancel_appointment(request.get_json())

@app.route('/getAvailabilities', methods=['POST'])
def get_availabilities():
    return appointment_mapper.get_availabilities(request.get_json())

@app.route('/addToCart', methods=['POST'])
def add_to_cart():
    return cart_mapper.add_to_cart(request.get_json())

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
#
# @app.route('/addAppointment', methods=['POST'])
# def add_appointment():
#     return appointment_mapper.book_appointment(request.get_json())


@app.route('/getUsers', methods=['POST'])
def get_users():
  return json.dumps(login_mapper.get_users())


@app.route('/registerUser', methods=['POST'])
def register_user():
   return login_mapper.register_user(request.form)
   # return render_template("index.html")


@app.errorhandler(401)
def page_not_found(e):
    return Response('<p>Login failed</p>')

@app.route("/login", methods=["POST"])
def login():
  return login_mapper.login(request.form)

@app.route("/logout")
@login_required
def logout():
    logout_user()
    return Response('<p>Logged out</p>')

@app.errorhandler(401)
def page_not_found(e):
    return Response('<p>Login failed</p>')

@app.route('/getCart')
def get_cart():
  return cart_mapper.get_cart()


@login_manager.user_loader
def user_loader(code):
    return login_mapper.user_loader(code)
