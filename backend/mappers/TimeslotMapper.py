from backend.tdg.TimeslotTdg import TimeslotTdg
from flask import json, jsonify
from backend.business_objects.Timeslot import Timeslot
import itertools 
import datetime

class TimeslotMapper:
    __instance = None

    def get_instance(app):
        if TimeslotMapper.__instance is None:
            TimeslotMapper.__instance = TimeslotMapper(app)
        else:
            print("Mapper instance already initialized!")
        return TimeslotMapper.__instance

    timeslots = {}

    def __init__(self, app):
        self.timeslot_tdg = TimeslotTdg(app)
        for timeslot in self.timeslot_tdg.get_timeslots():
            if(self.timeslots.get(timeslot['doctor_id']) is None):
                self.timeslots[timeslot['doctor_id']] = []
            self.timeslots[timeslot['doctor_id']].append(self.dbToTimeSlot(timeslot))

        for value in self.timeslots.values():
            value.sort(key=lambda timeslot: timeslot.date_time)

        #TODO: Alex FL and Phil L : add two previous datetimes as listeners to each timeslot
        
    def dbToTimeSlot(self, db_timeslot):
        return Timeslot(db_timeslot['id'], db_timeslot['doctor_id'], db_timeslot['patient_id'], \
                db_timeslot['date_time'], db_timeslot['room_id'], \
                bool(db_timeslot['is_booked']))

    def timeSlotToDB(self, timeslot):
        return timeslot.__dict__

    def get_all_appointments(self):
        return list(filter(lambda x: x.is_booked, itertools.chain.from_iterable(self.timeslots.values())))

    def book_appointment(self, req):
        date_time = datetime.datetime.combine(\
            datetime.datetime.strptime(req.get('date'), '%Y-%m-%d').date(), \
            datetime.datetime.strptime(req.get('time'), '%H:%M:%S').time())
        patient_id = req.get('patient_id')
        room_id = req.get('room_id')

        isSlotFound = False
        doctor_id = req.get('doctor_id')
        for timeslot in self.timeslots.get(int(doctor_id)):
            if(timeslot.date_time == date_time):
                isSlotFound = True
                if(not timeslot.is_booked):
                    timeslot.is_booked = True
                    timeslot.patient_id = patient_id
                    timeslot.room_id = room_id
                    return self.timeslot_tdg.update_timeslot(timeslot)
                else:
                    return "There is already an appointment with these parameters"
        
        if not isSlotFound:
            return "The doctor is not even available at this time slot"
    
    def cancel_appointment(self, req):
        id = req.get('id')
        doctor_id = req.get('doctor_id')
        for timeslot in self.timeslots.get(int(doctor_id)):
            if(timeslot.id == id):
                timeslot.patient_id = None
                timeslot.room_id = None
                timeslot.is_booked = False
        return self.timeslot_tdg.cancel_appointment(id)

    def get_availabilities(self, req):
        result = []
        reqDate = datetime.datetime.strptime(req.get('date'), '%Y-%m-%d').date()
        for doctor in self.timeslots.values():
            for timeslot in doctor:
                if((not timeslot.is_booked and timeslot.date_time.date() == reqDate)):
                    print(timeslot.date_time)
                    result.append(timeslot)
        return result

    def add_availability(self, req):
        date_time = datetime.datetime.combine(\
        datetime.datetime.strptime(req.get('date'), '%Y-%m-%d').date(), \
        datetime.datetime.strptime(req.get('time'), '%H:%M:%S').time())

        doctor_id = req.get('doctor_id')
        for timeslot in self.timeslots.get(int(doctor_id)):
            if(timeslot.doctor_id == doctor_id and timeslot.date_time == date_time):
                print("Doctor and timeslot already added!")
                return "There is already a timeslot with these parameters"

        id = self.timeslot_tdg.create_timeslot(doctor_id, date_time.strftime("%Y-%m-%d %H:%M:%S"))
        if(self.timeslots.get(doctor_id) is None):
            self.timeslots[doctor_id] = []
        self.timeslots[doctor_id].append(Timeslot(id, doctor_id, None, date_time, None, False))
        return "Timeslot added"