from flaskext.mysql import MySQL
from flask import json, jsonify
from flask import Flask, render_template
from backend.business_objects.User import User


class LoginTdg:

  def __init__(self, app):
    self.mysql = MySQL()

    app.config['MYSQL_DATABASE_USER'] = 'root'
    app.config['MYSQL_DATABASE_PASSWORD'] = ''
    app.config['MYSQL_DATABASE_DB'] = 'soen344'
    app.config['MYSQL_DATABASE_HOST'] = 'localhost'
    self.mysql.init_app(app)

  # Checked and works...kinda
  def get_users(self):
    connection = self.mysql.connect()
    cursor = connection.cursor()
    res = cursor.execute("SELECT * from people")
    row_headers = [x[0] for x in cursor.description]  # this will extract row headers
    data = []
    for row in cursor.fetchall():
      data.append(dict(zip(row_headers, row)))
    cursor.close()
    if (res is None):
      return False
    else:
      return data

  def add_user(self, code, password):
    connection = self.mysql.connect()
    cursor = connection.cursor()
    cursor.execute("""INSERT INTO people(id, password)
                      VALUES(%s, %s)""",
                   (code, password))
    cursor.execute("SELECT * FROM people ORDER BY id DESC")
    result = cursor.fetchone()
    connection.commit()
    return jsonify(result)


  def is_a_user(self, code):
    connection = self.mysql.connect()
    cursor = connection.cursor()
    res = cursor.execute("SELECT * from people WHERE id = %s",
                         (code))
    row_headers = [x[0] for x in cursor.description]  # this will extract row headers
    data = []
    for row in cursor.fetchall():
      data.append(dict(zip(row_headers, row)))
    cursor.close()
    # print(res)
    if (res == 0):
      return False
    else:
      return True


  def check_user_password(self, code, password):
    connection = self.mysql.connect()
    cursor = connection.cursor()
    res = cursor.execute("SELECT * from people WHERE id = %s AND password = %s",
                         (code, password))
    row_headers = [x[0] for x in cursor.description]  # this will extract row headers
    data = []
    for row in cursor.fetchall():
      data.append(dict(zip(row_headers, row)))
    cursor.close()
    if (res == 0):
      return False
    else:
      return True


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
