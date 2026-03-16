export interface CreateUserRequest {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export interface CreateUserResponse {
  id: string;
  name: string;
  email: string;
  created_at: string;
  updated_at: string;
}
