package getRequest;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;

import org.json.JSONArray;
import org.json.JSONException;

public class SchoolDataService {
    private Context context;
    private String host;


    public SchoolDataService(Context context, String host) {
        this.context = context;
        this.host = host;

    }

    public interface VolleyResponseListener {
        void onError(String message);

        void onResponse(JSONArray jsonObjectsArray) throws JSONException;
    }

    public void getResponse(String query, VolleyResponseListener volleyResponseListener) {
        String url1 = "http://" + this.host + ":8000/course_manager.php?" + query;


        JsonArrayRequest jsonArrayRequest = new JsonArrayRequest(
                Request.Method.GET,
                url1,
                null,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        //Toast.makeText(context, response.toString(), Toast.LENGTH_SHORT).show();
                        int i=0;

                        if (response.length() == 0) {
                            Toast.makeText(context, "Not found in database", Toast.LENGTH_SHORT).show();
                        } else {
                            try {

                                volleyResponseListener.onResponse(response);

                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }

                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        volleyResponseListener.onError(error.toString());
                    }
                }


        );
        DataServeSingleton.getInstance(context).addToRequestQueue(jsonArrayRequest);
    }

}
