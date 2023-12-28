Para rodar o projeto siga o passo a passo:

1. Entrar na pasta onde deseja salvar o projeto.
2. Clonar o projeto
3. cd challenge-api
4. composer install
5. cp .env.example .env
6. alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
7. sail up
8. sail artisan key:generate
9. sail artisan migrate
10. sail artisan queue:work redis
11. sail artisan test
