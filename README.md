# soen344

### How to setup

#### Backend
2. `virtualenv -p python3 backend/venv`
3. `source backend/venv/bin/activate`
4. `pip install -r requirements.txt`

*Important*: When you add a new package to python:
- Make sure you are in the (venv) shell when installing
- Immediately save the new requirements: `pip freeze > requirements.txt`
  - Command should be executed from the project root
- Setup your local db config in `backend/tdg/AbstractTdg`


#### Frontend
1. `cd frontend`
2. `npm install`
3. `npm build`

### How to run
Start the backend
`./run.sh`

Start the frontend (live-reload)
`cd frontend && npm start`

Build the frontend (package for prod)
`cd frontend && npm run build`

