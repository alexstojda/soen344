import datetime

class Timeslot:
    def __init__(self, id, doctor_id, patient_id, date_time, room_id, is_booked):
        self.id = id
        self.doctor_id = doctor_id
        self.patient_id = patient_id
        self.date_time = date_time
        self.room_id = room_id
        self.is_booked = is_booked
        self.is_free_for_one_hour = False

    def getNextDateTime(self):
        return self.date_time + datetime.timedelta(minutes=20)

    def notifyListeners(self):
        print("TODO: Timeslot.notifyListeners()")
        # Notify listeners that an update occured

    def serialize(self):
        return self.__dict__

    def getTime(self):
        return self.date_time.time()

    def getDate(self):
        return self.date_time.date()