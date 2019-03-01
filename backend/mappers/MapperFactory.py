from backend.mappers.CartMapper import CartMapper
from backend.mappers.TimeslotMapper import TimeslotMapper
from backend.mappers.UserMapper import UserMapper

class MapperFactory:
    def getMapper(app, type):
        if(type == "Cart"):
            return CartMapper(app)
        if(type == "Timeslot"):
            return TimeslotMapper(app)
        if(type == "User"):
            return UserMapper(app)

    getMapper = staticmethod(getMapper)