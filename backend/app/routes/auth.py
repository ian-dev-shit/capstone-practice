from flask import Blueprint, request, jsonify
from app.src.models import User
from app.decorator.middlewire import role_required # decorator for role-based access control
from app.src.database import db

auth_bp = Blueprint('auth', __name__, url_prefix='/api/auth')

# Route for user registration
@auth_bp.route('/register', methods=['POST'])
def register():
    data = request.get_json()


    if not data or not all(k in data for k in ('username', 'first_name', 'last_name', 'email', 'password')):
        return jsonify({'error': 'Missing required fields'}), 400
    
    if User.query.filter_by(username=data['username']).first() or User.query.filter_by(email=data['email']).first():
        return jsonify({'error': 'Username or email already exists'}), 400

    # Kunin ang role if meron pag wala default sa user role
    user_role = data.get('role', 'user')

    new_user = User(
        username=data['username'],
        first_name=data['first_name'],
        last_name=data['last_name'],
        email=data['email'],
        role=user_role
    )
    new_user.set_password(data['password'])

    db.session.add(new_user)
    db.session.commit()

    return jsonify({'message': f"User registered successfully as '{user_role}'"}), 201

# Route for user login
@auth_bp.route('/login', methods=['POST'])
def login():

    data = request.get_json()

    if not data or not all(k in data for k in ('username', 'password')):
        return jsonify({'error': 'Missing required fields'}), 400
    
    user = User.query.filter_by(username=data['username']).first()

    if not user or not user.check_password(data['password']):
        return jsonify({'error': 'Invalid username or password'}), 401
    
    return jsonify({
        'message': 'Login successful',
        'user': {
            'id': user.id,
            'username': user.username,
            'first_name': user.first_name,
            'last_name': user.last_name,
            'email': user.email,
            'role': user.role
        }
    }), 200


# Protected RBAC route example

# Endpoint na to para sa admin or manager
@auth_bp.route('/admin-dashboard', methods=['GET'])
@role_required('admin', 'manager')  # Only admin and manager can access this route
def admin_dashboard(current_user):
    # Mapapansin mo tinanggap nito ang `current_user` galing sa decorator
    return jsonify({
        'message': f"Welcome to the admin dashboard, {current_user.username}!",
        'user_role': current_user.role
    }), 200