from backend.tdg.AbstractTdg import AbstractTdg
from flask import json, jsonify
from flask import Flask, render_template


class UserTdg(AbstractTdg):
    def __init__(self, app):
        super().__init__(app)

    def get_doctors(self):
        connection = self.mysql.connect()
        cursor = connection.cursor()
        res = cursor.execute("""SELECT u.first_name, u.last_name, doctor.* FROM doctor
                                LEFT OUTER JOIN user u on doctor.user_id = u.id""")
        row_headers = [x[0] for x in cursor.description]  # this will extract row headers
        data = []
        for row in cursor.fetchall():
            data.append(dict(zip(row_headers, row)))
        cursor.close()
        if(res is None):
            return False
        else:
            return data
