# Tienda WordPress con contenedores

Plantilla de la práctica de Comercio Electrónico: WordPress + WooCommerce en Docker Compose, publicado en internet y medido con Google Analytics 4.

## Inicio rápido (estudiantes)

1. Pulsa **Use this template → Create a new repository** y créalo en tu cuenta.
2. En tu repositorio nuevo: **Code → Codespaces → Create codespace on main**.
3. Cuando abra la terminal, ejecuta:

   ```bash
   docker compose up -d
   docker compose ps
   ```

4. En la pestaña **PORTS**, abre el puerto **8080** (icono de globo) y completa el instalador de WordPress.
5. Para publicar la tienda: clic derecho en el puerto 8080 → **Port Visibility → Public**.

## Servicios

| Servicio    | Puerto | Para qué sirve                          |
|-------------|--------|-----------------------------------------|
| wordpress   | 8080   | La tienda (Apache + PHP + WordPress)    |
| phpmyadmin  | 8081   | Ver la base de datos (usuario `wpuser`) |
| db          | —      | MariaDB, solo en la red interna         |
| tunel       | —      | Cloudflare Quick Tunnel (opcional)      |

Túnel alternativo con Cloudflare (no requiere cuenta):

```bash
docker compose --profile publico up -d
docker compose logs tunel | grep trycloudflare.com
```

## Comandos útiles

```bash
docker compose logs -f wordpress   # ver logs (Ctrl+C para salir)
docker compose exec wordpress bash # entrar al contenedor
docker compose down                # detener (conserva los datos)
docker compose down -v             # detener y BORRAR los datos
```

Al terminar el curso, borra tu Codespace en https://github.com/codespaces para no consumir la cuota gratuita.
