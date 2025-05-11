import sys
import json
import pickle
import pandas as pd
import numpy as np

tanggal_start = sys.argv[1]
tanggal_end = sys.argv[2]
input_values = json.loads(sys.argv[3])

future_dates = pd.date_range(start=tanggal_start, end=tanggal_end, freq='M')
future_dates_julian = [pd.Timestamp(date).to_julian_date() for date in future_dates]
# Create a dictionary of input values
input_values_dict = {
    'Luas K1': input_values[0],
    'Suhu': input_values[1],
    'Kelembapan': input_values[2],
    'TotalPanenK1': input_values[3]
}
future_inputs = [np.array([date, *input_values_dict.values()]) for date in future_dates_julian]
# Load the trained model
if (list(input_values_dict.values())[0] == 66300 ):
    # Load the trained model
    with open('model1.pkl', 'rb') as file:
        model = pickle.load(file)
elif (list(input_values_dict.values())[0] == 47172):
    # Load the trained model
    with open('model2.pkl', 'rb') as file:
        model = pickle.load(file)
elif (list(input_values_dict.values())[0] == 20200):
    # Load the trained model
    with open('model3.pkl', 'rb') as file:
        model = pickle.load(file)
elif (list(input_values_dict.values())[0] == 148000):
    # Load the trained model
    with open('model4.pkl', 'rb') as file:
        model = pickle.load(file)
elif (list(input_values_dict.values())[0] == 210000):
    # Load the trained model
    with open('model5.pkl', 'rb') as file:
        model = pickle.load(file)
        
# Perform prediction
future_predictions = model.predict(future_inputs)
# Convert predictions to a JSON string
predictions_json = json.dumps(future_predictions.tolist())
# Return the predictions as JSON to PHP
print(predictions_json)
