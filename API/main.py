from flask import Flask, url_for, request
from flask_cors import CORS, cross_origin


app = Flask(__name__)
CORS(app,resources={r"/api/*": {"origins": "*"}})

@app.route('/')
@cross_origin()
def usage():
    return '''
    Usage:
    /api/discountCalculator?discount={}
    '''

@app.route("/api/discountCalculator" , methods=['GET'])
@cross_origin()
def discountCalculator():
    try:
        price = int(str(request.args.get('discount')))
    except:
        return "Usage:<br>/api/discountCalculator?discount={}"
    if price >= 10000:
        return {"discount": 0.12}
    elif price >= 5000:
        return {"discount": 0.08}
    elif price >= 3000:
        return {"discount": 0.03}
    else:
        return {"discount": 0}

app.run(
    host='127.0.0.1',
    port=8080,
)
