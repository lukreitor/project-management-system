export interface Project {
  id: number;
  name: string;
  progress: number;
  tasks?: Task[];
  created_at: string;
  updated_at: string;
}

export interface Task {
  id: number;
  title: string;
  difficulty: 'low' | 'medium' | 'high';
  completed: boolean;
  project_id: number;
  created_at: string;
  updated_at: string;
}

export interface CreateProjectRequest {
  name: string;
}

export interface CreateTaskRequest {
  title: string;
  difficulty: 'low' | 'medium' | 'high';
  project_id: number;
}

export interface ApiResponse<T> {
  data: T;
}

export interface ApiError {
  message: string;
  errors?: Record<string, string[]>;
}
