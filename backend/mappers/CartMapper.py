from backend.tdg.CartTdg import CartTdg
from backend.business_objects.Cart import Cart
class CartMapper:
    def __init__(self, app):
        self.cart_tdg = CartTdg(app)

    def get_cart(self):
        return self.cart_tdg.get_cart()

    def add_to_cart(self, req):
        patient = req.get('patient')
        doctor = req.get('doctor')
        date = req.get('date')
        return self.cart_tdg.add_to_cart(Cart(patient, doctor, date))
