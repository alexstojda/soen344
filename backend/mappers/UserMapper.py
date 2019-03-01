from backend.tdg.UserTdg import UserTdg

class UserMapper:
    __instance = None
    
    def get_instance(app):
        if UserMapper.__instance is None:
            UserMapper.__instance = UserMapper(app)
        else:
            print("Mapper instance already initialized!")
        return UserMapper.__instance
        
    def __init__(self, app):
        self.user_tdg = UserTdg(app)

    def get_doctors(self):
        return self.user_tdg.get_doctors()