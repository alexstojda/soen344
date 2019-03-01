from backend.mappers.CartMapper import CartMapper
from backend.mappers.TimeslotMapper import TimeslotMapper
from backend.mappers.UserMapper import UserMapper

class MapperFactory:
    def getMapper(app, type):
        if(type == "Cart"):
            return CartMapper.get_instance(app)
        if(type == "Timeslot"):
            return TimeslotMapper.get_instance(app)
        if(type == "User"):
            return UserMapper.get_instance(app)

    getMapper = staticmethod(getMapper)