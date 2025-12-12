import api from '@/lib/api';
import { Project, CreateProjectRequest, ApiResponse } from '@/types';

export const projectService = {
  async getAll(): Promise<Project[]> {
    const response = await api.get<ApiResponse<Project[]>>('/projects');
    return response.data.data;
  },

  async getById(id: number): Promise<Project> {
    const response = await api.get<ApiResponse<Project>>(`/projects/${id}`);
    return response.data.data;
  },

  async create(data: CreateProjectRequest): Promise<Project> {
    const response = await api.post<ApiResponse<Project>>('/projects', data);
    return response.data.data;
  },
};
