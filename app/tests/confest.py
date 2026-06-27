import pytest
from app import create_app
from app.src.database import db

@pytest.fixture
def app():
    # test config
    app = create_app()
    app.config.update({
        "TESTING": True,
        "SQLALCHEMY_DATABASE_URI": "sqlite:///:memory" # memory db dito papasok lahat ng testing data para di magulo yung totoong db
    })

    # set up ang database table bago mag e-test
    with app.app_context():
        db.create_all()
        yield app # dito mag run ang testing

        # Pag katapos ng testing linisin/sirain ang database
        db.session.remove()
        db.drop_all()

@pytest.fixture
def client(app):
    # Nag bibigay ng "test client" para mag send ng GET/POST request nang hindi run ang run.py
    return app.test_client()