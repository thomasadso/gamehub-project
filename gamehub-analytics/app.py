import os
from flask import Flask, request, jsonify
from pymongo import MongoClient

app = Flask(__name__)

MONGO_URI = "mongodb+srv://tccpcsf_db_user:resenias@clusterpracticalmongo.o08m1ks.mongodb.net/?retryWrites=true&w=majority"
client = MongoClient(MONGO_URI)
db = client['gamehub_analytics']
collection = db['estadisticas_juegos']

@app.route('/api/videojuegos', methods=['POST'])
def registrar_analitica():
    """Recibe la calificación de PHP y actualiza o crea las estadísticas en MongoDB[cite: 18, 48]."""
    data = request.get_json()
    
    videojuego_id = data.get('videojuego_id')
    titulo = data.get('titulo')
    calificacion = data.get('calificacion')
    
    if not videojuego_id or not calificacion:
        return jsonify({"error": "Faltan datos requeridos"}), 400
        
    # Buscamos si el juego ya tiene estadísticas guardadas en Mongo [cite: 38]
    juego_existente = collection.find_one({"videojuego_id": videojuego_id})
    
    if juego_existente:
        nuevo_total_votos = juego_existente['total_votos'] + 1
        nueva_suma = juego_existente['suma_calificaciones'] + calificacion
        nuevo_promedio = nueva_suma / nuevo_total_votos
        
        collection.update_one(
            {"videojuego_id": videojuego_id},
            {"$set": {
                "suma_calificaciones": nueva_suma,
                "total_votos": nuevo_total_votos,
                "promedio": nuevo_promedio
            }}
        )
    else:
        # Si es la primera reseña que registra este juego, creamos el documento [cite: 38]
        collection.insert_one({
            "videojuego_id": videojuego_id,
            "titulo": titulo,
            "suma_calificaciones": calificacion,
            "total_votos": 1,
            "promedio": float(calificacion)
        })
        
    return jsonify({"mensaje": "Módulo de analítica actualizado correctamente"}), 201

@app.route('/api/estadisticas', methods=['GET'])
def consultar_estadisticas():
    """Regresa el listado completo de estadísticas acumuladas[cite: 42]."""
    documentos = collection.find({}, {"_id": 0}) # Omitimos el campo _id interno de Mongo
    resultado = list(documentos)
    return jsonify(resultado), 200

@app.route('/api/mejores-videojuegos', methods=['GET'])
def mejores_videojuegos():
    """Regresa los videojuegos ordenados de mayor a menor calificación promedio[cite: 43]."""
    # Ordenamos por 'promedio' de manera descendente (-1)
    documentos = collection.find({}, {"_id": 0}).sort("promedio", -1)
    resultado = list(documentos)
    return jsonify(resultado), 200

if __name__ == '__main__':
    # Ejecución local en el puerto 5000
    app.run(debug=True, port=5000)
