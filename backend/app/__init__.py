# Application factory function -> dito build ang app
from flask import Flask
from .src.database import db
from .src.config import Config
from .src.models import User

def create_app():
    app = Flask(__name__)
    app.config.from_object(Config)  # Load configuration from Config class

    db.init_app(app)  # Initialize the database with the app

    # Import and register blueprints here 
    from app.routes.auth import auth_bp


    app.register_blueprint(auth_bp)

    # Automatic na gagawa ng database at mag se-seed ng default admin 
    with app.app_context():
        db.create_all()

        # -- SEEDING LOGIC--
        # Check muna kung may existing na na admin 
        admin_exists = User.query.filter_by(role='admin').first()

        if not admin_exists:
            print('Creating default admin acc')
            default_admin = User(
                username='admin',
                first_name='Christian',
                last_name='Masong',
                email='christian@gmail.com',
                role='admin'
            )

            # Gamitin ang method mula sa models para e-hashed ang password
            default_admin.set_password("admin123")

            db.session.add(default_admin)
            db.session.commit()
            print("Default admin created successfully")

    return app