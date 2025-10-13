# PROGRAMA PRINCIPAL EN RAMA DEV-TEMP

# 🧙‍♂️ Desafío Hobbyte

Un juego de estrategia en el que varios héroes se enfrentan a diferentes casillas dentro de una partida.  
Cada héroe cuenta con una habilidad única, y su objetivo es superar las pruebas asociadas a las casillas que coinciden con su habilidad.  
El sistema permite simular partidas, registrar resultados y gestionar usuarios (jugadores o administradores).

---

## 🕹️ Cómo jugar

1. **Crear una partida**
   
   - Cada jugador puede crear una partida indicando un nombre y el número total de casillas.

3. **Simular la partida**
   
   - Cada héroe juega automáticamente contra la casilla de su misma habilidad.
   - Si el héroe tiene éxito, se muestra como “casilla exitosa”.
   - La partida termina cuando se destapan todas las casillas.
  
---

## 👥 Gestión de usuarios

El sistema diferencia entre dos tipos de usuarios:

| Rol | Permisos |
|-----|-----------|
| **Jugador** | Crear y simular partidas |
| **Admin** | Cambiar contraseñas, crear usuarios, listar jugadores |

### 🔐 Login de usuario

- El usuario introduce su correo y contraseña para iniciar sesión, ya sea como administrador o jugador

### ⚙️ Acciones de administrador

🧑‍💼 Crear usuario
🔁 Cambiar contraseña
📋 Listar jugadores

### 🧾 Ejemplo de flujo completo

🔑 El jugador hace login

🎮 Crea una nueva partida

⚔️ Simula los enfrentamientos entre héroes y casillas

🏁 El sistema devuelve si los héroes ganaron o fueron derrotados

### 🧠 Autor

Desarrollado por: Diego García Ángel
