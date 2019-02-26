
from flask_login import  UserMixin

# TODO: change client from default and make it actually check the type of user form the DB
class User(UserMixin):
  # add actual password later, also names i guess
  def __init__(self, code, role=None):
    self.id = code
    if role is None:
      self.role = 0
    else:
      self.role = role


  def __repr__(self):
    return "%d" % (self.id)
