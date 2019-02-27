from flaskext.mysql import MySQL
from flask import json, jsonify
from flask import Flask, render_template
from backend.business_objects.Timeslot import Timeslot


class TimeslotTdg:

    def __init__(self, app):
        self.mysql = MySQL()

        app.config['MYSQL_DATABASE_USER'] = 'root'
        app.config['MYSQL_DATABASE_PASSWORD'] = ''
        app.config['MYSQL_DATABASE_DB'] = 'soen344'
        app.config['MYSQL_DATABASE_HOST'] = 'localhost'
        self.mysql.init_app(app)

    def get_timeslots(self):
        connection = self.mysql.connect()
        cursor = connection.cursor()
        res = cursor.execute("SELECT * from timeslot")
        row_headers = [x[0] for x in cursor.description]  # this will extract row headers
        data = []
        for row in cursor.fetchall():
            data.append(dict(zip(row_headers, row)))
        cursor.close()
        if(res is None):
            return None
        else:
            return data

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

    def update_timeslot(self, timeslot):
        connection = self.mysql.connect()
        cursor = connection.cursor()
        print(type(timeslot.patient_id))
        print(type(timeslot.room_id))
        print(type(timeslot.is_booked))
        print(type(timeslot.doctor_id))
        print(type(timeslot.date_time))
        cursor.execute("""UPDATE `timeslot` SET `patient_id` = '%s', 
                        `room_id` = '%s',
                        `is_booked` = '%s' 
                        WHERE `doctor_id` = '%s' AND `date_time` = %s
                        """, 
                        (int(timeslot.patient_id), int(timeslot.room_id), timeslot.is_booked, int(timeslot.doctor_id), timeslot.date_time))
        result = cursor.fetchone()
        connection.commit()
        return jsonify(result)

    def cancel_appointment(self, id):
        connection = self.mysql.connect()
        cursor = connection.cursor()
        print("8========DDDD")
        print(id)
        cursor.execute("""UPDATE `timeslot` SET `patient_id` = NULL, 
                        `room_id` = NULL,
                        `is_booked` = 0 
                        WHERE `id` = %s""", (id,))
        result = cursor.fetchone()
        connection.commit()
        return jsonify(result)

    def get_availabilities(self, date):
        connection = self.mysql.connect()
        cursor = connection.cursor()
        cursor.execute("""SELECT * FROM availability WHERE date = %s""", (date,))
        result = cursor.fetchall()
        connection.commit()
        return result