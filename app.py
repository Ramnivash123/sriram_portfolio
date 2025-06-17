from flask import Flask, render_template, request, make_response, redirect, url_for
from flask_jwt_extended import (
    JWTManager, create_access_token, jwt_required, get_jwt_identity,
    set_access_cookies, unset_jwt_cookies
)
from datetime import timedelta

app = Flask(__name__)
app.secret_key = 'your-secret-key'
app.config['JWT_SECRET_KEY'] = 'jwt-secret-key'
app.config['JWT_ACCESS_TOKEN_EXPIRES'] = timedelta(hours=1)
app.config['JWT_TOKEN_LOCATION'] = ['cookies']
app.config['JWT_COOKIE_CSRF_PROTECT'] = False  # Disable CSRF for demo; enable in production

jwt = JWTManager(app)

adminuser = {
    '8344041963': {'password': 'sriram110'}
}

@app.route('/')
def home():
    return render_template('index.html')

@app.route('/admin', methods=['GET', 'POST'])
def admin():
    if request.method == 'POST':
        mobile = request.form.get('mobile')
        password = request.form.get('password')

        user = adminuser.get(mobile)
        if user and user['password'] == password:
            access_token = create_access_token(identity=mobile)
            response = make_response(redirect('/dashboard'))
            set_access_cookies(response, access_token)
            return response
        else:
            return "Invalid credentials", 401
    return render_template('admin.html')

@app.route('/dashboard')
@jwt_required()
def dashboard():
    user_mobile = get_jwt_identity()
    return f"""
        <h1>Welcome to Dashboard, {user_mobile}!</h1>
        <form method="POST" action="/logout">
            <button type="submit">Logout</button>
        </form>
    """

@app.route('/logout', methods=['POST'])
def logout():
    response = make_response(redirect('/admin'))
    unset_jwt_cookies(response)
    return response

if __name__ == '__main__':
    app.run(debug=True)
