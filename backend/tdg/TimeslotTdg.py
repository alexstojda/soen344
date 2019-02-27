from flaskext.mysql import MySQL
from flask import json, jsonify
from flask import Flask, render_template
from backend.business_objects.Timeslot import Timeslot
from backend.tdg.AbstractTdg import AbstractTdg


class TimeslotTdg(AbstractTdg):

    def __init__(self, app):
        super().__init__(app)

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

    def update_timeslot(self, timeslot):
        connection = self.mysql.connect()
        cursor = connection.cursor()
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
        cursor.execute("""UPDATE `timeslot` SET `patient_id` = NULL, 
                        `room_id` = NULL,
                        `is_booked` = 0 
                        WHERE `id` = %s""", (id,))
        result = cursor.fetchone()
        connection.commit()
        return jsonify(result)
