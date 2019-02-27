from backend.tdg.AbstractTdg import AbstractTdg
from flask import json, jsonify
from flask import Flask, render_template
from backend.business_objects.Cart import Cart

class CartTdg(AbstractTdg):
    def __init__(self, app):
        super().__init__(app)

    def get_cart(self):
        connection = self.mysql.connect()
        cursor = connection.cursor()
        res = cursor.execute("SELECT * from cart")
        row_headers = [x[0] for x in cursor.description]  # this will extract row headers
        data = []
        for row in cursor.fetchall():
            data.append(dict(zip(row_headers, row)))
        cursor.close()
        if(res is None):
            return False
        else:
            return json.dumps(data)

    def add_to_cart(self, cart):
        connection = self.mysql.connect()
        cursor = connection.cursor()
        cursor.execute("""INSERT INTO cart(patient, doctor, date)
                        VALUES(%s, %s, %s)""",
                        (cart.patient, cart.doctor, cart.date))
        return jsonify(connection.commit())
