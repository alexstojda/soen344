from backend.tdg.AbstractTdg import AbstractTdg
from backend.business_objects.Appointment import Appointment

class AppointmentTdg(AbstractTdg):

    def __init__(self, app):
        super().__init__(app)

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

    def cancel_appointment(self, id):
        connection = self.mysql.connect()
        cursor = connection.cursor()
        cursor.execute("""DELETE FROM appointment WHERE id = %s""", (id,))
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
