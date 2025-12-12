import api from '@/lib/api';
import { Task, CreateTaskRequest, ApiResponse } from '@/types';

export const taskService = {
  async create(data: CreateTaskRequest): Promise<Task> {
    const response = await api.post<ApiResponse<Task>>('/tasks', data);
    return response.data.data;
  },

  async toggle(taskId: number): Promise<Task> {
    const response = await api.patch<ApiResponse<Task>>(`/tasks/${taskId}/toggle`);
    return response.data.data;
  },

  async delete(taskId: number): Promise<void> {
    await api.delete(`/tasks/${taskId}`);
  },
};
