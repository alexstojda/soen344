from backend.tdg.UserTdg import UserTdg

class UserMapper:
    def __init__(self, app):
        self.user_tdg = UserTdg(app)

    def get_doctors(self):
        return self.user_tdg.get_doctors()