import { useEffect, useState } from "react";
import { API_AUTH } from "./../config/api";

export const useDataApi = (initialUrl, initialData, initialType = "GET") => {
  const [data, setData] = useState(initialData);
  const [url, setUrl] = useState(initialUrl);
  const [type, setType] = useState(initialType);
  const [body, setBody] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(false);

  useEffect(() => {
    const fetchData = async () => {
      setLoading(true);
      setError(false);

      try {
        const options = {
          method: type,
          headers: {
            Authorization: API_AUTH,
            "Content-Type": "application/json"
          }
        };

        if (body) {
          options.body = JSON.stringify(body);
        }

        const response = await fetch(url, options);
        const result = await response.json();
        setData(result.data);
      } catch (error) {
        setError(true);
      }

      setLoading(false);
    };

    fetchData();
  }, [url, type, body]);

  const doFetch = url => {
    setUrl(url);
  };

  const doPost = (url, body) => {
    setType("POST");
    setBody(body);
    setUrl(url);
  };

  const doPut = (url, body) => {
    setType("PUT");
    setBody(body);
    setUrl(url + "/" + body._id);
  };

  const doDelete = (url, ids) => {
    setType("DELETE");
    setBody(ids);
    setUrl(url);
  };

  return { data, loading, error, doFetch, doPost, doPut, doDelete };
};