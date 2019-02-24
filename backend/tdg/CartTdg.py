from flaskext.mysql import MySQL
from flask import json, jsonify
from flask import Flask, render_template
from backend.business_objects.Cart import Cart

class CartTdg:
    def __init__(self, app):
        self.mysql = MySQL()

        app.config['MYSQL_DATABASE_USER'] = 'root'
        app.config['MYSQL_DATABASE_PASSWORD'] = ''
        app.config['MYSQL_DATABASE_DB'] = 'soen344'
        app.config['MYSQL_DATABASE_HOST'] = 'localhost'
        self.mysql.init_app(app)

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
