
from flask_login import  UserMixin

class User(UserMixin):
  # add actual password later, also names i guess
  def __init__(self, code):
    self.id = code
    self.name = "user"+str(id)

  def __repr__(self):
    return "%d/%s/%s" % (self.id, self.name, self.password)
