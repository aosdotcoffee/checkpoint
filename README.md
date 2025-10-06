# checkpoint

Checkpoint is a multi-purpose Ace of Spades server list manager. It allows users
to:

  - Combine multiple server lists into one that can be used by regular clients
  - Filter abusive servers
  - Verify server authenticity based on IP address

## Installation

### For development

You may install the project locally like any regular Laravel webapp:
```bash
git clone https://github.com/aosdotcoffee/checkpoint.git
composer install
php artisan key:generate
```

After specifying database credentials in `.env`, you may run the migrations and
create an admin account:
```bash
php artisan migrate
php artisan make:filament-user
```

You can now start the webserver:
```bash
composer dev
```

Your should now see an empty list at [localhost:8000](http://localhost:8000).
Visit http://localhost:8000/admin to login to the admin dashboard.

### For production

Docker is the recommended method for production deployments.

Example `docker compose` configuration:
```yaml
services:
  checkpoint:
    restart: 'unless-stopped'
    network_mode: 'host'
    init: true

    environment:
      CHECKPOINT_HOST: '[::1]'
      CHECKPOINT_PORT: '8093'

    build:
      context: '/usr/src/checkpoint'
      dockerfile: 'Dockerfile'

    volumes:
      - '/srv/services/checkpoint/.env:/app/.env:ro'
```

## License

[GNU Affero General Public License v3.0](LICENSE)
