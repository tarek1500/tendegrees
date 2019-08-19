# TenDegrees

## List of available APIs
| Method | URL | Description |
| --- | --- | --- |
| `POST` | `api/register` | Create an account. |
| `POST` | `api/login` | Send a login request to the application. |
| `GET` | `api/logout` | Send a logout request to the application. |
| `POST` | `api/token/check` | Check if the access token is valid. |
| `POST` | `api/token/refresh` | Refresh the given access token. |
| `GET` | `api/profile` | Get the user's profile. |
| `PATCH` | `api/profile` | Update the user's profile. |
| `GET` | `api/following` | Get a list of followers by the user. |
| `GET` | `api/followers` | Get a list of the user's followers. |
| `POST` | `api/follow` | Follow another user. |
| `POST` | `api/unfollow` | Unfollow another user. |
| `GET` | `api/timeline` | Get the timeline of the user. |
| `GET` | `api/tweet` | Get a list of the user's tweets. |
| `POST` | `api/tweet` | Create a tweet for the user. |
| `GET` | `api/tweet/{id}` | Show the given-id-tweet of the user. |
| `PATCH` | `api/tweet/{id}` | Update the given-id-tweet of the user. |
| `DELETE` | `api/tweet/{id}` | Delete the given-id-tweet of the user. |