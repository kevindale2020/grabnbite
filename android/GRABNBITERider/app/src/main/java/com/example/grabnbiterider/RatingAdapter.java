package com.example.grabnbiterider;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.RatingBar;
import android.widget.TextView;

import com.squareup.picasso.Picasso;

import java.util.List;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

public class RatingAdapter extends ArrayAdapter {
    public LayoutInflater inflater;
    private Context context;
    private List<Rating> ratingList;

    public RatingAdapter(List<Rating> ratingList, Context context) {
        super(context, R.layout.custom_layout3, ratingList);
        this.context = context;
        this.ratingList = ratingList;
    }

    @NonNull
    @Override
    public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {

        inflater = LayoutInflater.from(context);

        if (inflater == null) {
            inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        }

        if (convertView == null) {
            convertView = inflater.inflate(R.layout.custom_layout3, null, true);
        }

        ImageView imageView = convertView.findViewById(R.id.image_view);
        TextView name = convertView.findViewById(R.id.name);
        TextView date = convertView.findViewById(R.id.date);
        TextView comment = convertView.findViewById(R.id.comment);
        RatingBar customerRating = convertView.findViewById(R.id.rating);

        final Rating rating = ratingList.get(position);

        StringBuilder sb = new StringBuilder("http://192.168.137.1:8000/images/users/");
        sb.append(rating.getImage());

        String imageURL = sb.toString();
        Picasso.get().load(imageURL).into(imageView);

        name.setText(rating.getFname()+" "+rating.getLname());
        date.setText(rating.getDate());
        comment.setText(rating.getFeedback());
        customerRating.setRating(rating.getRate());


        return convertView;
    }
}
