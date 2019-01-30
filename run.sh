#!/bin/bash
source backend/venv/bin/activate
FLASK_APP=run.py FLASK_DEBUG=1 flask run