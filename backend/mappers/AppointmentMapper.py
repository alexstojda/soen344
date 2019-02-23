from backend.tdg.AppointmentTdg import AppointmentTdg
from backend.business_objects.Appointment import Appointment

class AppointmentMapper:
    def __init__(self, app):
        self.appointment_tdg = AppointmentTdg(app)

    def get_appointments(self):
        return self.appointment_tdg.get_appointments()

    def book_appointment(self, req):
        patient = req.get('patient')
        doctor = req.get('doctor')
        room = req.get('room')
        date = req.get('date')
        if(self.appointment_tdg.get_appointment(patient, doctor, date) == None):
            return self.appointment_tdg.add_appointment(Appointment(patient, doctor, room, date))
        else:
            return "There is already an appointment with these parameters"
    
    def cancel_appointment(self, req):
        print(req.get('appointment_id'))
        id = req.get('appointment_id')
        return self.appointment_tdg.cancel_appointment(id)