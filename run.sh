#!/bin/bash
source backend/venv/bin/activate
FLASK_APP=app.py FLASK_DEBUG=1 flask run