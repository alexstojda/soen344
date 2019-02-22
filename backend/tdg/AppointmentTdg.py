from flaskext.mysql import MySQL
from flask import json, jsonify
from flask import Flask, render_template
from backend.business_objects.Appointment import Appointment


class AppointmentTdg:

    def __init__(self, app):
        self.mysql = MySQL()

        app.config['MYSQL_DATABASE_USER'] = 'root'
        app.config['MYSQL_DATABASE_PASSWORD'] = ''
        app.config['MYSQL_DATABASE_DB'] = 'soen344'
        app.config['MYSQL_DATABASE_HOST'] = 'localhost'
        self.mysql.init_app(app)

    def get_appointments(self):
        connection = self.mysql.connect()
        cursor = connection.cursor()
        res = cursor.execute("SELECT * from appointment")
        row_headers = [x[0] for x in cursor.description]  # this will extract row headers
        data = []
        for row in cursor.fetchall():
            data.append(dict(zip(row_headers, row)))
        cursor.close()
        if(res is None):
            return False
        else:
            return data
    
    def get_appointment(self, patient, doctor, date):
        connection = self.mysql.connect()
        cursor = connection.cursor()
        res = cursor.execute("SELECT * from appointment WHERE patient = %s AND doctor = %s AND date = %s", (patient, doctor, date))
        row_headers = [x[0] for x in cursor.description]  # this will extract row headers
        data = []
        for row in cursor.fetchall():
            data.append(dict(zip(row_headers, row)))
        cursor.close()
        print("8=================D")
        print(res)
        if(res == 0):
            return None
        else:
            return data
    def add_appointment(self, appointment):
        connection = self.mysql.connect()
        cursor = connection.cursor()
        cursor.execute("""INSERT INTO appointment(patient, doctor, room, date)
                        VALUES(%s, %s, %s, %s)""", 
                        (appointment.patient, appointment.doctor, appointment.room, appointment.date))
        cursor.execute("SELECT * FROM appointment ORDER BY id DESC")
        result = cursor.fetchone()
        connection.commit()
        return jsonify(result)