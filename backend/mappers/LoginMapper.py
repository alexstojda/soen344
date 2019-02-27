from flask import redirect

from backend.tdg.LoginTdg import LoginTdg
from backend.business_objects.User import User
import flask

from flask_login import LoginManager, UserMixin, login_required, login_user, logout_user


class LoginMapper:
  def __init__(self, app):
    self.login_tdg = LoginTdg(app)

  def get_users(self):
    return self.login_tdg.get_users()

  def register_user(self, req):
    code = req.get('code')
    password = req.get('password')
    if (self.login_tdg.is_a_user(code) == False):
      self.login_tdg.add_user(code, password)
      return "You in my man"
    else:
      return "There is already a user with that code"

  def login(self, form):
    code = form['code']
    password = form['password']
    if (self.login_tdg.check_user_password(code, password)):
      user = User(code)
      login_user(user)
      return redirect("homePage")
    else:
      return "who do you think you is?"

  def load_user(self, userid):
    return User(userid)

  def user_loader(self, code):
    if (self.login_tdg.is_a_user(code) == False):
      return
    user = User(code)
    # user.id = code
    return user
