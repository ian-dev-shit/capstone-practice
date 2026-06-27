# Decorators for RBAC

from functools import wraps
from flask import request, jsonify
from app.src.models import User

def role_required(*roles):
    def decorator(f):
        @wraps(f)
        def decorated_function(*args, **kwargs):
            # Kunin and user ID from the header para malaman kung sino yung nag request
            user_id = request.headers.get('X-User-Id')

            if not user_id:
                return jsonify({'error': 'Authentication required. Missing user ID.'}), 404
            
            #Hanapin ang user sa database gamit ang user ID
            user = User.query.get(user_id)
            if not user:
                return jsonify({'error': 'User not found.'}), 404
            
            # Check kung ang role ng user ay kasama sa mga role na required (*roles)
            if user.role not in roles:
                return jsonify({'error': f'Unauthorized. Requiring role: {list(roles)}'}), 403
            
            # kung successful, ipasa ang user object sa decorated function
            return f(user, *args, **kwargs)
        return decorated_function
    return decorator