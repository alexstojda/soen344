from flaskext.mysql import MySQL
from flask import json, jsonify
from flask import Flask, render_template
from backend.business_objects.Appointment import Appointment


class LoginTdg:

  def __init__(self, app):
    self.mysql = MySQL()

    app.config['MYSQL_DATABASE_USER'] = 'root'
    app.config['MYSQL_DATABASE_PASSWORD'] = ''
    app.config['MYSQL_DATABASE_DB'] = 'soen344'
    app.config['MYSQL_DATABASE_HOST'] = 'localhost'
    self.mysql.init_app(app)

  def get_users(self):
    connection = self.mysql.connect()
    cursor = connection.cursor()
    res = cursor.execute("SELECT * from user")
    row_headers = [x[0] for x in cursor.description]  # this will extract row headers
    data = []
    for row in cursor.fetchall():
      data.append(dict(zip(row_headers, row)))
    cursor.close()
    if (res is None):
      return False
    else:
      return data

  def get_user_login(self, code, password):
    connection = self.mysql.connect()
    cursor = connection.cursor()
    res = cursor.execute("SELECT * from user WHERE code = %s AND password = %s",
                         (code, password))
    row_headers = [x[0] for x in cursor.description]  # this will extract row headers
    data = []
    for row in cursor.fetchall():
      data.append(dict(zip(row_headers, row)))
    cursor.close()
    print("8=================D")
    print(res)
    if (res == 0):
      return None
    else:
      return data

  # def add_appointment(self, appointment):
  #   connection = self.mysql.connect()
  #   cursor = connection.cursor()
  #   cursor.execute("""INSERT INTO appointment(patient, doctor, date)
  #                       VALUES(%s, %s, %s)""",
  #                  (appointment.patient, appointment.doctor, appointment.date))
  #   cursor.execute("SELECT * FROM appointment ORDER BY id DESC")
  #   result = cursor.fetchone()
  #   connection.commit()
  #   return jsonify(result)