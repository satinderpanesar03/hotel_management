import axios from 'axios';

const API_URL = 'http://127.0.0.1:8000/api/dashboard';

const apiService = {
  async fetchProperties(search = '', location = '') {
    try {
      const response = await axios.get(API_URL, {
        params: { search, location },
      });
      return response.data;
    } catch (error) {
      console.error("There was an error fetching properties!", error);
      throw error;
    }
  }
};

export default apiService;
